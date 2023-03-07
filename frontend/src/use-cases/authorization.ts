import { Observable } from "rxjs";
import { Either, left, right } from "@sweet-monads/either";

export namespace AuthorizationUseCase {
  export namespace Ports {
    export interface Api {
      fetch(): Observable<Either<Error, Dtos.User>>
    }

    export interface Emiter {
      emit(either: Either<Error, Dtos.User>): void
    }
  }

  export namespace Dtos {
    export type User = {
      name: string,
      email: string,
      role: {
        id: string
        name: string
      },
      account: {
        email: string
      }
    }
  }

  export class UseCase {
    constructor(
      private readonly _api: Ports.Api,
      private readonly _emiter: Ports.Emiter
    ){}

    auth() {
      this._api.fetch().subscribe((e: Either<Error, Dtos.User>) => {
        e.mapLeft((error: Error) => this._emiter.emit(left(error)))

        e.map((user: Dtos.User) => {
          this._emiter.emit(right(user))
        })
      })
    }
  }
}
